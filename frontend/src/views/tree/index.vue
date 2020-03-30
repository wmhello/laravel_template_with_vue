<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="12">
        <el-card class="box-card">
          <div slot="header" class="clearfix"><span>element UI树形组件</span></div>
          <el-input v-model="filterText" placeholder="Filter keyword" style="margin-bottom:30px;" />
          <el-tree ref="tree2" :data="data2" :props="defaultProps" :filter-node-method="filterNode" class="filter-tree" default-expand-all />
        </el-card>
      </el-col>
      <el-col :span="12" >
        <el-card class="box-card">
          <div slot="header" class="clearfix"><span>自定义tree组件</span></div>
          <tree v-for="child in list" :item.sync="child" :key="child.id" @treeItemSelect="treeItemSelect"></tree>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import treeData from './data';
import tree from '@/components/Tree';
export default {
  data() {
    return {
      filterText: '',
      data2: [
        {
          id: 1,
          label: 'Level one 1',
          children: [
            {
              id: 4,
              label: 'Level two 1-1',
              children: [
                {
                  id: 9,
                  label: 'Level three 1-1-1'
                },
                {
                  id: 10,
                  label: 'Level three 1-1-2'
                }
              ]
            }
          ]
        },
        {
          id: 2,
          label: 'Level one 2',
          children: [
            {
              id: 5,
              label: 'Level two 2-1'
            },
            {
              id: 6,
              label: 'Level two 2-2'
            }
          ]
        },
        {
          id: 3,
          label: 'Level one 3',
          children: [
            {
              id: 7,
              label: 'Level two 3-1'
            },
            {
              id: 8,
              label: 'Level two 3-2'
            }
          ]
        }
      ],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
          treeData,
          list: [],
          currentId: null
    };
  },
  components: {
    tree
  },
  created() {
    this.initTreeData();
  },
  watch: {
    filterText(val) {
      this.$refs.tree2.filter(val);
    }
  },

  methods: {
    filterNode(value, data) {
      if (!value) return true;
      return data.label.indexOf(value) !== -1;
    },
    addData() {
      console.log('传到id为' + this.currentId + '子元素');
      // 用api获取原始数据
      // 原始数据为了显示，必须进行转化
      this.initTreeData();
    },
    resetSelect() {
      let reduceDataFunc = data => {
        data.map((m, i) => {
          m.isSelect = false;
          if (m.children.length > 0) {
            reduceDataFunc(m.children);
          }
        });
      };
      reduceDataFunc(this.list);
    },
    treeItemSelect(relation) {
      this.resetSelect();
      let arrRelation = relation.split(/#+/g);
      arrRelation.pop();
      arrRelation.shift();
      this.currentId = parseInt(arrRelation[arrRelation.length - 1]);
      let setSelect = (data, arr, level) => {
        data.forEach((item, index) => {
          // console.log(item.id + "==>" + parseInt(arr[level]));
          if (item.id === parseInt(arr[level])) {
            item.isSelect = true;
            if (item.children.length > 0) {
              item.isSelect = false;
              if (arr.length === level + 1) {
                item.isSelect = true;
              }
              setSelect(item.children, arr, level + 1);
            }
          } else {
            item.isSelect = false;
          }
        });
      };
      setSelect(this.list, arrRelation, 0);
      // this.$forceUpdate();
    },
    initTreeData() {
      console.log('处理前的:', JSON.parse(JSON.stringify(this.treeData)));
      // 这里一定要转化，要不然他们的值监听不到变化
      let tempData = JSON.parse(JSON.stringify(this.treeData));
      let reduceDataFunc = (data, level, pid, relation) => {
        data.map((m, i) => {
          m.isSelect = false;
          m.children = m.children || [];
          m.level = level;
          m.pid = pid;
          m.relation = relation + '#' + m.id + '#';
          if (m.children.length > 0) {
            reduceDataFunc(m.children, level + 1, m.id, m.relation);
          }
        });
      };
      reduceDataFunc(tempData, 1, 0, '0');
      console.log('处理后的:', tempData);
      this.list = tempData;
    }
  }
};
</script>
