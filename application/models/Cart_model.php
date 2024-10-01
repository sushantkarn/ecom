<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Cart_model extends CI_Model
{
    function add_to_cart($data, $check_status = TRUE)
    {
        $data = escape_array($data);
        $product_variant_id = explode(',', $data['product_variant_id']);
        $qty = explode(',', $data['qty']);
        $dateString = date('Y-m-d H:i:s');
        $time = strtotime($dateString);
        if ($check_status == TRUE) {
            $check_current_stock_status = validate_stock($product_variant_id, $qty);
            if (!empty($check_current_stock_status) && $check_current_stock_status['error'] == true) {
                $check_current_stock_status['csrfName'] = $this->security->get_csrf_token_name();
                $check_current_stock_status['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($check_current_stock_status));
                return true;
            }
        }

        for ($i = 0; $i < count($product_variant_id); $i++) {
            $cart_data = [
                'user_id' => $data['user_id'],
                'product_variant_id' => $product_variant_id[$i],
                'qty' => $qty[$i],
                'is_saved_for_later' => (isset($data['is_saved_for_later']) && !empty($data['is_saved_for_later']) && $data['is_saved_for_later'] == '1') ? $data['is_saved_for_later'] : '0',
                'added_timestamp' => $time,
            ];
            if ($qty[$i] == 0) {
                $this->remove_from_cart($cart_data);
            } else {
                if ($this->db->select('*')->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->get('cart')->num_rows() > 0) {
                    $this->db->set($cart_data)->where(['user_id' => $data['user_id'], 'product_variant_id' => $product_variant_id[$i]])->update('cart');
                } else {
                    $this->db->insert('cart', $cart_data);
                }
            }
        }
        return false;
    }

    function remove_from_cart($data)
    {
        if (isset($data['user_id']) && !empty($data['user_id'])) {
            $this->db->where('user_id', $data['user_id']);
            if (isset($data['product_variant_id'])) {
                $product_variant_id = explode(',', $data['product_variant_id']);
                $this->db->where_in('product_variant_id', $product_variant_id);
            }
            return $this->db->delete('cart');
        } else {
            return false;
        }
    }

    function get_user_cart($user_id, $is_saved_for_later = 0, $product_variant_id = '')
    {

        $q = $this->db->join('product_variants pv', 'pv.id=c.product_variant_id')
            ->join('products p', 'p.id=pv.product_id')
            ->join('`taxes` tax', 'tax.id = p.tax', 'LEFT')
            ->join('`seller_data` sd', 'sd.user_id = p.seller_id')
            ->where(['c.user_id' => $user_id, 'p.status' => '1', 'pv.status' => 1, 'sd.status' => 1, 'qty !=' => '0', 'is_saved_for_later' => $is_saved_for_later]);
        if (!empty($product_variant_id)) {
            $q->where('c.product_variant_id', $product_variant_id);
        }
        $res =  $q->select('c.*,p.is_prices_inclusive_tax,p.name,p.type,p.id,p.slug as product_slug,p.image,p.short_description,p.seller_id,p.minimum_order_quantity,p.quantity_step_size,p.pickup_location,pv.weight,p.total_allowed_quantity,pv.price,pv.special_price,pv.id as product_variant_id,tax.percentage as tax_percentage')->order_by('c.id', 'DESC')->get('cart c')->result_array();


        if (!empty($res)) {
            $res = array_map(function ($d) {
                $d['pickup_location'] = (isset($d['pickup_location']) && !empty($d['pickup_location']) && $d['pickup_location'] != 'NULL') ? $d['pickup_location'] : '';
                $d['special_price'] = $d['special_price'] != '' && $d['special_price'] != null && $d['special_price'] > 0 && $d['special_price'] < $d['price'] ? $d['special_price'] : $d['price'];
                $percentage = (isset($d['tax_percentage']) && intval($d['tax_percentage']) > 0 && $d['tax_percentage'] != null) ? $d['tax_percentage'] : '0';
                // if ((isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 0) || (!isset($d['is_prices_inclusive_tax'])) && $percentage > 0) {
                //     $price_tax_amount = $d['price'] * ($percentage / 100);
                //     $special_price_tax_amount = $d['special_price'] * ($percentage / 100);
                // } else {
                //     $price_tax_amount = 0;
                //     $special_price_tax_amount = 0;
                // }

                if (isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 0) {
                    $price_tax_amount = $d['price'] * ($percentage / 100);
                    $special_price_tax_amount = $d['special_price'] * ($percentage / 100);
                } else {
                    $price_tax_amount = $d['price'] - ($d['price'] * (100 / (100 + $percentage)));
                    $special_price_tax_amount =   $d['special_price'] - ($d['special_price'] * (100 / (100 + $percentage)));;
                }

                // $tax_amount = $d['special_price'] * ($d['tax_percentage'] / 100);
                $price = isset($d['special_price']) && !empty($d['special_price']) && $d['special_price'] > 0 ? $d['special_price'] : $d['price'];

                if (isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 1) {
                    $tax_amount  = $price - ($price * (100 / (100 + $d['tax_percentage'])));
                } else {
                    $tax_amount = $price * ($d['tax_percentage'] / 100);
                }


                // $d['price'] =  $d['price'] + $price_tax_amount;
                // $d['special_price'] =  $d['special_price'] + $special_price_tax_amount;
                if ((isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 0) || (!isset($d['is_prices_inclusive_tax'])) && $percentage > 0) {
                    $d['price'] =  ($d['price'] +  $price_tax_amount);
                } else {
                    $d['price'] =  $d['price'];
                }
                if ((isset($d['is_prices_inclusive_tax']) && $d['is_prices_inclusive_tax'] == 0) || (!isset($d['is_prices_inclusive_tax'])) && $percentage > 0) {
                    $d['special_price'] =  ($d['special_price'] + $special_price_tax_amount);
                } else {
                    $d['special_price'] =  $d['special_price'];
                }
                $d['minimum_order_quantity'] =  (isset($d['minimum_order_quantity']) && !empty($d['minimum_order_quantity'])) ? $d['minimum_order_quantity'] : 1;
                if (isset($d['special_price']) && $d['special_price'] != '' && $d['special_price'] != null && $d['special_price'] > 0 && $d['special_price'] < $d['price'] ? $d['special_price'] : $d['price']) {
                    $d['net_amount'] =  number_format($d['special_price'] - $special_price_tax_amount, 2);
                    $d['net_amount'] = str_replace(",", "", $d['net_amount']);
                } else {
                    $d['net_amount'] =  number_format($d['price'] - $price_tax_amount, 2);
                    $d['net_amount'] = str_replace(",", "", $d['net_amount']);
                }

                $d['tax_percentage'] =  (isset($d['tax_percentage']) && !empty($d['tax_percentage'])) ? $d['tax_percentage'] : '';
                $d['tax_amount'] =  (isset($tax_amount) && !empty($tax_amount)) ? str_replace(",", "", number_format($tax_amount, 2)) : 0;
                if (isset($d['special_price']) && $d['special_price'] != '' && $d['special_price'] != null && $d['special_price'] > 0 && $d['special_price'] < $d['price'] ? $d['special_price'] : $d['price']) {
                    $d['sub_total'] =  ($d['special_price'] * $d['qty']);
                } else {
                    $d['sub_total'] =  ($d['price'] * $d['qty']);
                }
                $d['quantity_step_size'] =  (isset($d['quantity_step_size']) && !empty($d['quantity_step_size'])) ? $d['quantity_step_size'] : 1;
                $d['total_allowed_quantity'] =  isset($d['total_allowed_quantity']) && !empty($d['total_allowed_quantity']) ? $d['total_allowed_quantity'] : '';
                $d['product_variants'] = get_variants_values_by_id($d['product_variant_id']);
                return $d;
            }, $res);
        }
        return $res;
    }

    function old_user_cart($user_id, $cart)
    {
        if (isset($user_id) && isset($cart)) {
            $cart_data = [
                'is_saved_for_later' => 1
            ];
            $old_cart = json_decode($cart);
            $product_variant_ids = implode(',', $old_cart);
            $product_variant_ids = array_map('intval', explode(',', $product_variant_ids));
            // print_r($product_variant_ids);
            // print_r(gettype($product_variant_ids));
            // die;
            // $sql = "UPDATE cart SET is_saved_for_later = 1 WHERE product_variant_id IN ($product_variant_ids) AND user_id = $user_id";
            $this->db->set('is_saved_for_later', 1);
            $this->db->where_in('product_variant_id', $product_variant_ids);
            $this->db->where('user_id', $user_id);
            $this->db->update('cart');
            // echo $this->db->last_query();
            // $this->db->query($sql, array($user_id));

        }
    }

    function cart_item_remainder()
    {
        $firebase_project_id = get_settings('firebase_project_id');
        $service_account_file = get_settings('service_account_file');

        $time = date('Y-m-d H:i:s');
        $currentTime = strtotime($time);
        
        $this->db->select('*');
        $this->db->from('cart');
        $this->db->where('notification_sended', '0');
        $this->db->where('is_saved_for_later', '0');
        $this->db->group_by('user_id');
        $data = $this->db->get()->result_array();

        foreach ($data as $cart_item) {
            $fcm_ids = [];

            $timeDifference = $currentTime - $cart_item['added_timestamp'];

            if ($timeDifference >= 1*3600) {
            // if ($timeDifference >= 20) {
                $user_data = fetch_details('users', ['id' => $cart_item['user_id']], 'fcm_id');
                foreach ($user_data as $user_fcm) {
                    $fcm_ids[] = $user_fcm['fcm_id'];
                }
            }
            $registrationIDs_chunks[0] = $fcm_ids;
            $fcmMsg = array(
                'title' => "👋 Your Cart Misses You!",
                'body' => "Come back and complete your purchase. Great deals await! 🎉",
                'type' => "cart",
            );
            // print_r($fcmMsg);
            if (isset($firebase_project_id) && isset($service_account_file) && !empty($firebase_project_id) && !empty($service_account_file)) {
                $fcmFields = send_notification('', $registrationIDs_chunks, $fcmMsg);
                // die;
                $this->db->set('notification_sended', 1);
                $this->db->where('user_id',  $cart_item['user_id']);
                $this->db->update('cart');
            }
        }
        return $fcmFields;
    }

    // function get_old_user_cart($user_id, $cart)
    // {
    //     // print_r($user_id);
    //     // print_r($cart[0]['user_cart']);
    //     $old_cart = json_decode($cart[0]['user_cart']);
    //     // print_r(gettype($old_cart));
    //     //     $ids_str = 
    //     $product_variant_id = implode(',', $old_cart);
    //         print_r($product_variant_id);
    //     die;
    //     for ($i = 0; $i < count($old_cart); $i++) {

    //         $cart_data = [
    //             'user_id' => $user_id,
    //             'product_variant_id' => $product_variant_id[$i],
    //             'qty' => 1,
    //             'is_saved_for_later' => '0'
    //         ];
    //         if (isset($user_id) && isset($cart)) {
    //             $this->db->insert('cart', $cart_data);
    //         }
    //     }
    // }
}
