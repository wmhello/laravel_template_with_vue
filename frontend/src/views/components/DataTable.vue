<template>
<div>
  <table class="dataintable todo-list">
    <tbody>
    <tr>
        <th>标识</th>
        <th>名称</th>
        <th>描述</th>
        <th>时间</th>
    </tr>
    <tr v-for="item in list" :key="item.id">
        <td>{{item.id}}</td>
        <td>{{item.name}}</td>
        <td>{{item.desc}}</td>
        <td>{{item.date}}</td>
    </tr>
  </tbody>
</table>
</div>

</template>

<script>
export default {
   name: 'DataTable',
   props: ['list'],

}
</script>


<style>
  .dataintable{
    border: 1px solid #aaa;
    width: 100%;
}
  .dataintable th{
    padding: 0;
    height: 36px;
    vertical-align: middle;
    text-align: center;
    background-color: #F5F5F5;
    border: 1px solid #ddd;
  }
    table.todo-list{
    border-collapse: collapse;
  }

  table.todo-list td{
      padding: 0;
      height: 36px;
      text-align: center;
      vertical-align: middle;
      background: #fdfcf8 no-repeat center;
      border: 1px solid #ddd;
  }
</style>


