export const rules = {
        app_id: [{ required: true, message: "请输入APP_ID", trigger: "blur" }],
      app_secret: [{ required: true, message: "请输入APP_Secret", trigger: "blur" }],

};

export function Model() {
                 this.app_id = null   
               this.app_secret = null   
               this.type = null   
               this.status = null   

}

export function SearchModel() {
       this.app_id = null
     this.app_secret = null

}