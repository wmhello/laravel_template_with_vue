<template>
  <div class="login-container">
    <form class="el-form login-main el-form--label-left">
      <h3 class="title">
        <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">系统登录</font></font>
      </h3>
      <div class="el-form-item is-required">
        <div class="el-form-item__content" style="margin-left: 0px;">
          <div class="el-input">
            <input type="text" autocomplete="off" placeholder="账号" class="el-input__inner" />
          </div>
        </div>
      </div>
      <div class="el-form-item is-required">
        <div class="el-form-item__content" style="margin-left: 0px;">
          <div class="el-input">
            <input type="password" autocomplete="off" placeholder="密码" class="el-input__inner" />

          </div>
        </div>
      </div>
      <div class="el-form-item btn-box">
        <div class="el-form-item__content" style="margin-left: 0px;">
          <button type="button" class="el-button el-button--primary">
            <span>
              <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">登录</font></font>
            </span>
          </button>
          <button type="button" class="el-button el-button--default">
            <span>
              <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">重置</font></font>
            </span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  export default {

  }

</script>

<style>

.login-container {
  width: 100vw;
  height: 100vh;
  background-image: url('@/assets/login_bg.77503e29.png');
  background-size: cover;
  overflow: hidden
}

.login-container .login-main {
  border-radius: 5px;
  background-clip: padding-box;
  margin: 280px auto;
  width: 350px;
  padding: 35px 35px 15px;
  background: #fff;
  border: 1px solid #eaeaea;
  -webkit-box-shadow: 0 0 25px #cac6c6;
  box-shadow: 0 0 25px #cac6c6
}

.login-container .login-main .btn-box,
.login-container .login-main h3 {
  text-align: center
}
</style>
