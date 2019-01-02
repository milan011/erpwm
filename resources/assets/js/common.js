
// 公共方法

//判断数组对象是否为空
export function isEmpty(v) {
  return (
    (Array.isArray(v) && v.length == 0) || (Object.prototype.isPrototypeOf(v) && Object.keys(v).length == 0)
  );
}

//判断是否谷歌浏览器
export function isChorme() {
  	var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
    //判断是否Chrome浏览器

 	return (userAgent.indexOf("Chrome") > -1);
}