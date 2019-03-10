<template>
  <div id="allmap" :style="{height:mapHeight+'px'}"></div>
</template>

<script>
import BMap from 'BMap'

export default {
  name: 'dashboard',
  data() {
    return {
      mapHeight: 500,
      points: [
        [
          116.417854,
          39.921988,
          '车牌：XXXX<br>司机：王大力<br>电话：13800138000<br>时间：2018-03-16 18：00：05<br>状态:停车超时报警<br>速度:1.2KM/H<br>位置：北京市东城区正义路甲5号'
        ],
        [
          116.412222,
          39.912345,
          '车牌：ZZZZ<br>司机：王大力<br>电话：13800138000<br>时间：2018-03-16 18：00：05<br>状态:停车超时报警<br>速度:1.2KM/H<br>位置：北京市东城区正义路甲5号'
        ]
      ]
    }
  },
  mounted() {
    // 百度地图API功能
    const map = new BMap.Map('allmap', { enableMapClick: false })
    map.centerAndZoom(new BMap.Point(116.417854, 39.921988), 12)
    map.enableScrollWheelZoom()

    var geolocation = new BMap.Geolocation()
    geolocation.enableSDKLocation()
    geolocation.getCurrentPosition(function(r) {
      if (this.getStatus() === 0) {
        map.panTo(r.point)
      }
    }, { enableHighAccuracy: true })

    this.mapHeight =
      (window.innerHeight ||
        document.documentElement.clientHeight ||
        document.body.clientHeight) - 85

    // fetchCarsGps().then(res => {
    var marker = new BMap.Marker(
      new BMap.Point(116.406605, 39.921585)
    )
    // 创建标注
    var content = '车牌：YYYY<br>司机：王大力<br>电话：13800138000<br>时间：2018-03-16 18：00：05<br>状态:停车超时报警<br>速度:1.2KM/H<br>位置：北京市东城区正义路甲5号'
    map.addOverlay(marker) // 将标注添加到地图中
    addClickHandler(content, marker)
    // });

    var opts = {
      width: 250, // 信息窗口宽度
      // height: 80,     // 信息窗口高度
      title: '地标', // 信息窗口标题
      enableMessage: false // 设置不允许信息窗发送短息
    }

    function addClickHandler(content, marker) {
      marker.addEventListener('click', function(e) {
        openInfo(content, e)
      })
    }
    function openInfo(content, e) {
      var p = e.target
      var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat)
      var infoWindow = new BMap.InfoWindow(content, opts) // 创建信息窗口对象
      map.openInfoWindow(infoWindow, point) // 开启信息窗口
    }
  },
  methods: {

  },
  computed: {

  }
}
</script>

<style rel="stylesheet/scss" lang="scss">
#allmap {
  width: 100%;
  height: 100%;

  p {
    margin-left: 5px;
    font-size: 14px;
  }
}
.anchorBL {
  display: none;
}
</style>
