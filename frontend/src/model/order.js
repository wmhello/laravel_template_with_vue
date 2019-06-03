export function Model (order_number='', merchant_number='', merchant_name='', order_time = new Date(), status = 1, remark='') {
    this.order_number = order_number
    this.merchant_number = merchant_number
    this.merchant_name = merchant_name
    this.order_time = order_time
    this.status = status
    this.remark = remark
}

export function SearchModel(order_number='', merchant_number='', merchant_name='', status = null) {
   this.order_number = order_number
   this.merchant_number = merchant_number
   this.merchant_name = merchant_name
   this.status = status
}

let Rules = {
  order_number: [
    { required: true, message: '请填写订单', trigger: 'blur' },
  ],
  merchant_number: [
    { required: true, message: '请填写商户编号', trigger: 'blur' },
  ],
}

export {Rules}
