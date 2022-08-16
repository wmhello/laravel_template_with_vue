<template>
  <div class="navbar">
    <hamburger
      :is-active="sidebar.opened"
      class="hamburger-container"
      @toggleClick="toggleSideBar"
    />

    <breadcrumb class="breadcrumb-container" />

    <div class="right-menu">
      <screenfull id="screenfull" class="right-menu-item hover-effect" />

      <el-dropdown class="avatar-container" trigger="click">
        <div class="avatar-wrapper">
          <img :src="avatar + '?imageView2/1/w/80/h/80'" class="user-avatar" />
          <i class="el-icon-caret-bottom" />
        </div>
        <el-dropdown-menu slot="dropdown" class="user-dropdown">
          <router-link to="/"
            ><el-dropdown-item>首页</el-dropdown-item></router-link
          >
          <router-link to="/personal/index"
            ><el-dropdown-item>个人设置</el-dropdown-item></router-link
          >
          <el-dropdown-item divided
            ><span style="display: block" @click="logout"
              >退出</span
            ></el-dropdown-item
          >
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Breadcrumb from "@/components/Breadcrumb";
import Hamburger from "@/components/Hamburger";
import screenfull from "@/components/Screenfull";
import setting from "@/settings";
export default {
  components: {
    Breadcrumb,
    Hamburger,
    screenfull,
  },
  computed: {
    ...mapGetters(["sidebar", "avatar"]),
  },
  async created() {
    if (setting.isWebsocket && setting.isSingle) {
      // 如果设置了单独的用户登录，则之前登录的其他用户都必须退出
      if (!window.websocketHandle) {
        window.websocketHandle = {};
      }
      window.websocketHandle.logout = async (item, res) => {
        this.$alert("该用户已经在其它地方登录，此操作将强制下线", "友情提示", {
          confirmButtonText: "确定",
          callback: async (action) => {
            await this.logout();
            delete window.websocketHandle.logout;
          },
        });
      };
    }
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch("app/toggleSideBar");
    },
    async logout() {
      let tab = {
        fullPath: "/dashboard",
        meta: {
          affix: true,
          icon: "dashboard",
          title: "面板",
        },
        name: "Dashboard",
        path: "/dashboard",
        title: "面板",
      };
      this.$store.dispatch("tagsView/delOthersViews", tab).then(async () => {
        await this.$store.dispatch("user/logout");
        this.$router.push(`/login`);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background 0.3s;
    -webkit-tap-highlight-color: transparent;

    &:hover {
      background: rgba(0, 0, 0, 0.025);
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background 0.3s;

        &:hover {
          background: rgba(0, 0, 0, 0.025);
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
.user-dropdown {
  top: 50px !important;
}
</style>
