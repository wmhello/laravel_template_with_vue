 <template>
  <div class="tree">
    <!-- 页面显示部分是tree-item -->
    <div
      class="tree-item"
      :style="treeClass"
      @mouseenter="isMove= true"
      @mouseleave="isMove = false"
      @click="toggleChildren()"
    >
      <img
        v-if="item.children.length>0 && !this.showChildren"
        style="width: 16px;height: 16px;"
        src="@/assets/open.png"
        alt
      />
      <img v-else src="@/assets/close.png" style="width: 16px;height: 16px;" alt />
      {{item.title + item.level}}
      <div v-if="isMove" class="toolbar">
        <img data-msg="新建" src="@/assets/new.png" alt @click="add(item.id)" />
        <img data-msg="保存" src="@/assets/save.png" alt @click="save(item.id)" />
        <img data-msg="打印" src="@/assets/print.png" alt @click="print(item.id)" />
      </div>
      <div v-if="item.isSelect" class="select-item">我选择了</div>
    </div>
    <template v-if="showChildren">
      <tree
        v-for="child in item.children"
        @treeItemSelect="treeItemSelect"
        :item.sync="child"
        :key="child.id"
      ></tree>
    </template>
  </div>
</template>
 
 <script>
export default {
  name: "tree",
  props: {
    item: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      showChildren: false,
      isMove: false
    };
  },
  computed: {
    treeClass() {
      let result = this.item.level === 1 ? 30 : 30 + (this.item.level - 1) * 30;
      return {
        "margin-left": result + "px"
      };
    }
  },
  created() {},
  methods: {
    showId(id) {
      alert(id);
    },
    toggleChildren() {
      this.showChildren = !this.showChildren;
      this.$emit("treeItemSelect", this.item.relation);
    },
    treeItemSelect(relation) {
      this.$emit("treeItemSelect", relation);
    },
    add(id) {
      alert("新建数据" + id);
    },
    save(id) {
      alert("保存数据" + id);
    },
    print(id) {
      alert("打印数据" + id);
    }
  }
};
</script>

<style>
.tree {
  width: 500px;
}
.tree-item {
  display: flex;
  height: 40px;
  line-height: 40px;
  align-items: center;
  cursor: pointer;
}
.select-item {
  margin-left: auto;
}
.toolbar {
  margin-left: 10px;
  display: flex;
  align-items: center;
}
.toolbar img {
  margin-left: 10px;
  cursor: pointer;
  display: block;
  position: relative;
}
.toolbar img::after {
  position: absolute;
  top: 40px;
  left: 0px;
  width: 200%;
  height: 100%;
  text-align: center;
  font-size: 12px;
  content: attr(data-msg);
  transition: all 500ms;
  opacity: 0;
  color: #000;
}

.toolbar img:hover::after {
  opacity: 1;
}
</style>