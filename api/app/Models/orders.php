<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    //
    protected $fillable = ['id', 'user_id', 'post_info_id', 'created_time', 'sender_no', 'wh_code', 'mailType', 'logistics_order_no', 'batch_no', 'biz_product_no', 'weight', 'volume', 'length', 'width', 'height', 'postage_total', 'postage_currency', 'contents_total_weight', 'contents_total_value', 'transfer_type', 'battery_flag', 'pickup_notes', 'insurance_flag', 'insurance_amount', 'undelivery_option', 'valuable_flag', 'declare_source', 'declare_type', 'declare_curr_code', 'barcode', 'forecastshut', 'printcode', 'mail_sign', 'sender_name', 'sender_company', 'sender_post_code', 'sender_phone', 'sender_mobile', 'sender_email', 'sender_id_type', 'sender_id_no', 'sender_nation', 'sender_province', 'sender_city', 'sender_county', 'sender_address', 'sender_gis', 'sender_linker', 'receiver_name', 'receiver_company', 'receiver_post_code', 'receiver_phone', 'receiver_mobile', 'receiver_email', 'receiver_id_type', 'receiver_id_no', 'receiver_nation', 'receiver_province', 'receiver_city', 'receiver_county', 'receiver_address', 'receiver_gis', 'receiver_linker', 'items', 'waybill_no', 'is_pay', 'pay_amount', 'discount', 'is_in', 'is_out', 'note', 'created_at', 'updated_at'];
    
    
}
