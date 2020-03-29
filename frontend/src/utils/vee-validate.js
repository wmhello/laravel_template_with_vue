import { localize, extend} from 'vee-validate';
//引入zh-CN
import zh_CN from 'vee-validate/dist/locale/zh_CN';

//js使用方式，指定语言、修改默认提示，添加自定义验证
localize('zh_CN', {
  // 设置了这个之后提示信息会变成中文的
  messages: zh_CN.messages
});

localize({
  en: {
    names: {
      email: 'E-mail Address',
      password: 'Password'
    }
  },
  zh_CN: {
    names: {
      username: '用户名',
      password: '密码'
    },
  }
});

import * as rules from 'vee-validate/dist/rules';

Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});


