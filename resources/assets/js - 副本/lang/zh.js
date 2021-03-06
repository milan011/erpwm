export default {
  route: {
    dashboard: '首页',
    form: '表单',
    errorPages: '错误页面',
    page401: '401',
    page404: '404',
    user:'用户管理',
    userCreate:'添加用户',
    passwordReset:'重置密码',
    userUpdate:'修改用户',
    userDelete:'删除用户',
    permission:'权限管理',
    permissionAdd:'添加权限',
    permissionUpdate:'修改权限',
    permissionDelete:'删除权限',
    role:'角色管理',
    roleAdd:'添加角色',
    roleUpdate:'修改角色',
    roleDelete:'删除角色',
    package:'套餐管理',
    packageAdd:'添加套餐',
    packageUpdate:'修改套餐',
    packageDelete:'删除套餐',
    manager:'客户经理',
    managerAdd:'添加客户经理',
    managerUpdate:'修改客户经理',
    managerDelete:'删除客户经理',
    info:'信息管理',
    infoAdd:'添加信息',
    infoUpdate:'修改信息',
    infoDelete:'删除信息',
    infoDianXin:'电信信息',
    infoDianXinAdd:'添加电信信息',
    infoDianXinUpdate:'修改电信信息',
    infoDianXinDelete:'删除电信信息',
    InfoStatistics: '信息统计',
    goods: '商品管理',
    service: '业务管理',
    inventory: '库存管理',
    inventoryList: '库存礼品',
    inventoryDetail: '库存明细',
    serviceList: '业务列表',
    serviceDetail: '业务明细',

  },
  navbar: {
    logOut: '退出登录',
    passwordReset: '修改密码',
    dashboard: '首页',
    github: '项目地址',
    screenfull: '全屏',
    theme: '换肤',
    size: '布局大小'
  },
  login: {
    title: '淘车乐系统登录',
    logIn: '登录',
    username: '账号',
    password: '密码',
    any: '随便填',
    thirdparty: '第三方登录',
    thirdpartyTips: '本地不能模拟，请结合自己业务进行模拟！！！'
  },
  permission: {
    roles: '你的权限',
    switchRoles: '切换权限',
    name: '权限名称',
    guard_name: '权限标识',
    description: '权限描述',
    group: '分组',
  },

  components: {
    documentation: '文档',
    tinymceTips: '富文本是管理后台一个核心的功能，但同时又是一个有很多坑的地方。在选择富文本的过程中我也走了不少的弯路，市面上常见的富文本都基本用过了，最终权衡了一下选择了Tinymce。更详细的富文本比较和介绍见',
    dropzoneTips: '由于我司业务有特殊需求，而且要传七牛 所以没用第三方，选择了自己封装。代码非常的简单，具体代码你可以在这里看到 @/components/Dropzone',
    stickyTips: '当页面滚动到预设的位置会吸附在顶部',
    backToTopTips1: '页面滚动到指定位置会在右下角出现返回顶部按钮',
    backToTopTips2: '可自定义按钮的样式、show/hide、出现的高度、返回的位置 如需文字提示，可在外部使用Element的el-tooltip元素',
    imageUploadTips: '由于我在使用时它只有vue@1版本，而且和mockjs不兼容，所以自己改造了一下，如果大家要使用的话，优先还是使用官方版本。'
  },
  table: {
    dynamicTips1: '固定表头, 按照表头顺序排序',
    dynamicTips2: '不固定表头, 按照点击顺序排序',
    dragTips1: '默认顺序',
    dragTips2: '拖拽后顺序',
    title: '标题',
    importance: '重要性',
    type: '类型',
    remark: '点评',
    search: '搜索',
    add: '添加',
    export: '导出',
    import: '导入',
    reviewer: '审核人',
    id: '序号',
    date: '创建',
    author: '作者',
    readings: '阅读数',
    status: '状态',
    actions: '操作',
    edit: '编辑',
    show: '查看',
    editPermissions: '权限设置',
    setRoles: '角色设置',
    publish: '发布',
    draft: '草稿',
    delete: '删除',
    cancel: '取 消',
    confirm: '确 定'
  },
  errorLog: {
    tips: '请点击右上角bug小图标',
    description: '现在的管理后台基本都是spa的形式了，它增强了用户体验，但同时也会增加页面出问题的可能性，可能一个小小的疏忽就导致整个页面的死锁。好在 Vue 官网提供了一个方法来捕获处理异常，你可以在其中进行错误处理或者异常上报。',
    documentation: '文档介绍'
  },
  excel: {
    export: '导出',
    selectedExport: '导出已选择项',
    placeholder: '请输入文件名(默认excel-list)'
  },
  zip: {
    export: '导出',
    placeholder: '请输入文件名(默认file)'
  },
  theme: {
    change: '换肤',
    documentation: '换肤文档',
    tips: 'Tips: 它区别于 navbar 上的 theme-pick, 是两种不同的换肤方法，各自有不同的应用场景，具体请参考文档。'
  },
  tagsView: {
    refresh: '刷新',
    close: '关闭',
    closeOthers: '关闭其它',
    closeAll: '关闭所有'
  },
  user: {
    id: '序号',
    name: '用户名',
    nick_name: '姓名',
    telephone: '电话',
    password: '密码',
    passwordRepeat: '确认密码',
    email: '邮箱',
    wx_number: '微信',
    address: '地址',
    status: '状态',
    remark: '备注',
    creater: '创建者',
    add: '添加'
  },
  role: {
    id: '序号',
    name: '角色',
    guard_name: '标识',
    add: '添加',
    description: '角色描述',
  },
  package: {
    id: 'ID',
    name: '套餐名称',
    bloc: '所属集团',
    package_price: '套餐价格',
    creater_id: '创建者',
    month_nums: '还款月数',
    netin: '入网时间',
    remark: '备注',
    status: '状态',
    return_price: '返还金额',
    return_moon_price: '月返还金额',
    return_month: '返还月份',
    nums: '还款时间几个月',
    add: '添加',
    show: '套餐详情',
  },
  manager: {
    id: 'ID',
    name: '客户经理',
    telephone: '电话',
    wx_number: '微信号码',
    creater: '创建者',
    email: '邮箱',
    address: '地址',
    remark: '备注',
    status: '状态',
    add: '添加',
    first_letter: '首字母',
  },
  info: {
    id: 'ID',
    code: '序号',
    name: '客户',
    telephone: '电话',
    user_telephone: '客户电话',
    manager_name: '客户经理',
    manager_telephone: '客户经理电话',
    project_name: '项目名称',
    new_telephone: '入网号码',
    uim_number: 'UIM码',
    side_number: '副卡',
    is_jituan: '集团卡',
    old_bind: '绑老卡',
    netin: '入网',
    collections: '收款',
    balance_month: '当前结算月',
    collections_type: '收款方式',
    creater: '创建者',
    package_month: '套餐返还月',
    package: '套餐',
    side_number_num: '副卡',
    side_uim_number: '副卡UIM码',
    side_uim_number_num: '副卡UIM数量',
    return_record: '返还记录',
    remark: '备注',
    status: '状态',
    side_number_add: '添加',
    side_number_remove: '删除',
    add: '添加',
  },
  infoDianxin: {
    id: '序号',
    // code: '序号',
    name: '套餐名称',
    return_telephone: '返款号码',
    jituan: '集团名称',
    manager: '客户经理',
    jiakuan: '价款',
    refunds: '返款金额',
    exampleExcelDown: '标准表下载',
    yongjin: '佣金方案',
    balance_month: '结算月',
    netin: '返还日期',   
    creater: '创建者',
    import: '导入日期',
    remark: '备注',
    status: '状态',
    infoDeal: '信息处理',
    add: '添加',
  },
  infoStatistics: {
    name: '业务员',
    info_nums_all: '主卡数(不绑老卡|绑老卡)',
    old_bind: '绑老卡',
    not_old_bind: '不绑老卡',
    old_bind: '不绑老卡|绑老卡',
    side_nums_all: '副卡数(不绑老卡|绑老卡)',
    subtotal: '小计(不绑老卡|绑老卡)',
    total: '总数',
    netin: '入网日期',   
  },
  goods: {
    name: '名称',
    brand: '品牌',
    goods_from: '进货单位',
    type: '类别',
    in_price: '进价',
    ruku_price: '金额',
    goods_spec: '规格',
    goods_unit: '单位',
    goods_num: '数量',
    is_food: '食品类',  
    bottom_price: '底价',  
    remark: '备注',  
  },
  service:{
    name: '业务名称',
    type: '返还方式',
    return_price: '返还金额',
    return_ratio: '返还比例',
    remark: '备注',
  },
  serviceDetail:{
    serviceName: '业务名称',
    goodsList: '赠送礼品',
    customer: '客户',
    goodsAdd: '添加',
    goodsRemove: '删除',
    customer_telephone: '客户电话',
    charge_price: '收费金额',
    goods_num: '赠品数量',
    goods_cost: '赠品金额',
    inventory_percentage: '返佣',
    inventory_profit: '利润',
    inventoryer: '业务员',
    inventer_ticheng: '提成',
    remark: '备注',
  },
  inventory:{
    goodsName: '礼品',
    inventoryNow: '当前库存',
    inventoryAdd: '添加库存',
  },
  inventoryDetail:{
    serviceName: '业务',
    goodsName: '礼品',
    inventory_code: '单号',
    type: '出/入库',
    inventory_num: '数量',
    remark: '备注',
  },
}
