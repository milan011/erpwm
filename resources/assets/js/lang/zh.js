export default {
  route: {
    dashboard: '首页',
    form: '表单',
    errorPages: '错误页面',
    page401: '401',
    page404: '404',
    user:'用户管理',
    userList:'用户列表',
    userCreate:'添加用户',
    passwordReset:'重置密码',
    userUpdate:'修改用户',
    userDelete:'删除用户',
    permission:'权限管理',
    permissionAdd:'添加权限',
    permissionUpdate:'修改权限',
    permissionDelete:'删除权限',
    role:'角色权限',
    roleManger:'角色管理',
    roleAdd:'添加角色',
    roleUpdate:'修改角色',
    roleDelete:'删除角色',
    shop:'门店管理',
    set:'设置',
    pettyCash: '小额现金',
    pettyCashManage: '管理功能',
    pettyCashSelect: '查询及报告',
    pettyCashSet: '维护',
    pcTypeTab: '标签类别',
    pcTab: '标签维护',
    pcExpenses: '费用种类',
    pcAssignCashToTab: '预支现金',
    pcClaimExpensesFromTab: '报销申请',
    pcAuthorizeExpense: '费用审批',
    asset: '资产管理',
    assetManage: '管理功能',
    fixedAssets: '资产列表',
    assetSelect: '查询及报告',
    assetSet: '维护',
    fixedAssetCategorie: '固定资产种类',
    fixedAssetLocation: '固定资产地点',
    maintenanceTask: '维护任务',
    fixedAssetItem: '资产列表',
    setBase:'基础信息',
    setGatherPay: '应收应付',
    setSale: '销售相关',
    saleType: '销售方式',
    paymentMethod: '付款方式',
    salesMan: '销售员',
    area: '销售区域',
    salesGLPosting: '销售收入科目',
    cOGSGLPosting: '销售成本科目',
    discountMatrix: '折扣',
    setLocation: '库存设置',
    locations: '仓库管理',
    unitsOfMeasure: '计量单位',
    mRPCalendar: 'MRP 日历',
    mRPDemandType: '需求种类',
    department: '部门维护',
    stockCategory: '物料组',
    customerType: '客户类型',
    supplierType: '供应商类型',
    creditStatus: '信用等级',
    paymentTerm: '付款条款',
    purchorderAuth: '采购订单授权',
    taxCategories:'税目维护',
    taxProvinces: '纳税区域',
    taxGroups:'税收组',
    freightCost: '发运点',
    taxAuthorities:'税收主管部门及税率',
    periodsInquiry: '会计期间',
    shipper: '承运人',

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
    content: '详情',
    editPermissions: '权限设置',
    setRoles: '角色设置',
    publish: '发布',
    draft: '草稿',
    delete: '删除',
    passReset: '密码重置',
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
    userShop: '门店',
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
    editStockCategory: '易耗品维护',
  },
  taxCategories: {
    taxcatid: '序号',
    taxcatname: '税目',
  },
  taxProvinces: {
    taxprovinceid: '序号',
    taxprovincename: '纳税区域',
  },
  taxGroups: {
    taxgroupid: '序号',
    taxgroupdescription: '税收组',
    setGroupTax: '税种分配',
  },
  taxAuthorities:{
    taxid : '序号',
    description : '税种描述',
    taxglcode : '销项税会计科目',
    purchtaxglaccount : '进项税会计科目',
    bank : '纳税专户银行',
    bankacctype : '银行交易类型',
    bankacc : '银行账户',
    bankswift : '银行单编号',
    setTaxRate: '税率设置'
  },
  taxauthrates:{
    id : '序号',
    taxauthority : '税种',
    dispatchtaxprovince : '纳税区域',
    taxcatid : '税种',
    taxrate : '税率',
  },
  periodsInquiry:{
    periodno: '期间编号',
    lastdate_in_period: '期末日期',
  },
  saleType:{
    id: '序号',
    typeabbrev: '编号',
    sales_type : '名称',
  },
  customerType:{
    typeid: '序号',
    typename: '名称',
  },
  supplierType:{
    typeid: '序号',
    typename: '名称',
  },
  creditStatus:{
    id: '序号',
    reasoncode: '等级编号',
    reasondescription: '描述',
    dissallowinvoices: '允许开发票',
    status: '状态',
  },
  paymentTerm:{
    id: '序号',
    termsindicator: '付款编码',
    terms: '付款描述',
    daysbeforedue: '到期天数',
    dayinfollowingmonth: '付款日期',
    panmentEnd: '付款截止',
    status: '状态',
    paymenttype: '截止类型',
  },
  purchorderAuth:{
    id: '序号',
    userid: '用户',
    uid: 'UID',
    currabrev: '币种',
    cancreate: '创建订单',
    authlevel: '授权水平',
    offhold: '发行发票',
  },
  paymentMethod:{
    paymentid: '序号',
    paymentname: '付款方式',
    paymenttype: '可用于付款',
    receipttype: '可用于收款',
    usepreprintedstationery: '使用套打',
    opencashdrawer: 'POS现金抽屉',
    status: '状态',
  },
  salesMan:{
    id : '编号',
    salesmanname : '姓名',
    smantel : '电话',
    smanfax : '传真',
    commissionrate1 : '提成比率1',
    breakpoint : '分隔点',
    commissionrate2 : '提成比率2',
    current : '当前?',
    status : '状态',
  },
  area:{
    id: '序号',
    areadescription: '区域',
  },
  shipper: {
    shipper_id: '序号',
    shippername: '承运人',
  },
  salesGLPosting: {
    id : '序号',
    area : '销售区域',
    stkcat : '库存种类',
    discountglcode : '销售折扣科目',
    salesglcode : '销售收入科目',
    salestype : '销售方式 / 价格表',
  },
  cOGSGLPosting: {
    id : '序号',
    area : '销售区域',
    stkcat : '库存种类',
    glcode : '会计科目',
    salestype : '销售方式 / 价格表',
  },
  freightCost: {
    shipcostfromid: '序号',
    locationfrom: '仓库',
    destinationcountry: '目的地',
    destination: '目的地',
    shipperid: '物流',
    cubrate: '元/m³',
    kgrate: '元/公斤',
    maxkgs: '重量上限(kg)',
    maxcub: '体积上限(m³)',
    fixedprice: '固定运费',
    minimumchg: '最低收费',
  },
  discountMatrix:{
    id: '序号',
    salestype: '销售方式',
    discountcategory: '折扣类别',
    quantitybreak: '数量分隔',
    discountrate: '折扣率 %',
  },
  stockCategory: {
    id: '序号',
    categorydescription: '物料描述',
    stocktype: '凭证类型',
    stockact: '存货科目',
    adjglact: '盘点差异',
    issueglact: '易耗品',
    purchpricevaract: '价格差',
    materialuseagevarac: '数量差',
    wipact: '在制品',
    defaulttaxcatid: '默认税目',
    setOrther: '附加信息',
    label: '标签',
    controltype: '控制类型',
    defaultvalue: '默认值',
    maximumvalue: '最大值',
    reqatsalesorder: '销售订单需求',
    minimumvalue: '最小值',
    numericvalue: '数字值',
  },
  locations: {
    id: '序号',
    loccode: '仓库编码',
    locationname: '仓库名称',
    deladd1: '交货地址1',
    deladd2: '交货地址2',
    deladd3: '交货地址3',
    deladd4: '交货地址4',
    deladd5: '交货地址5',
    deladd6: '交货地址6',
    contact: '保管员',
    tel: '电话号码',
    fax: '传真号码',
    email: '电子邮件',
    taxprovinceid: '纳税区域',
    cashsalecustomer: '默认柜台销售客户',
    cashsalebranch: '柜台销售分公司',
    internalrequest: '允许内部领用',
  },
  unitsOfMeasure: {
    unitid: '序号',
    unitname: '单位',
  },
  mRPCalendar:{
    id: '序号',
    calendardate: '日期',
    daynumber: '累计生产日',
    manufacturingflag: '是否工作日',
  },
  mRPDemandType:{
    id: '序号',
    description: '需求种类',
  },
  department: {
    departmentid: '序号',
    description: '部门名称',
    authoriser: '授权人',
  },
  pcTypeTab: {
    id: '序号',
    typetabdescription: '标签描述',
    editPcExpenses: '费用种类对应',
  },
  pcTab: {
    id: '序号',
    tabcode: '标签名称',
    usercode: '用户',
    typetabcode: '标签类别',
    currency: '币种',
    tablimit: '限额',
    assigner: '分配人',
    authorizer: '授权人',
    glaccountassignment: '现金预支总账账户',
    glaccountpcash: '所属总账账户',
  },
  pcExpenses:{
    id: '序号',
    description: '费用名称',
    glaccount: '科目编号',
    tag: '标签',
  },
  pcAssignCashToTab:{
    counterindex: '序号',
    tabcode: '标签',
    date: '预支款日期',
    codeexpense: '费用代码',
    amount: '金额(元)',
    authorized: '授权日期',
    posted: '授权',
    notes: '备注',
    receipt: '收款',
  },
  pcClaimExpensesFromTab:{
    counterindex: '序号',
    tabcode: '标签',
    date: '预支款日期',
    codeexpense: '报销费用',
    amount: '金额(元)',
    authorized: '授权日期',
    posted: '授权',
    notes: '备注',
    receipt: '收款',
  },
  pcAuthorizeExpense:{
    counterindex: '序号',
    tabcode: '标签',
    date: '预支款日期',
    codeexpense: '报销费用',
    amount: '金额(元)',
    authorized: '授权日期',
    posted: '授权',
    notes: '备注',
    receipt: '收款',
  },
  fixedAssetCategorie: {
    id: '序号',
    categorydescription: '名称',
    costact: '固定资产成本总账',
    depnact: '损益折旧总账',
    disposalact: '处置损益的总账',
    accumdepnact: '资产负债表中累计折旧的总账',
    defaultdepnrate: '',
    defaultdepntype: '',
  },
  fixedAssetLocation: {
    id: '序号',
    locationid: '仓库',
    locationdescription: '名称',
    parentlocationid: '父地点',
  },
  maintenanceTask: {
    taskid: '序号',
    assetid: '资产',
    taskdescription: '任务描述',
    frequencydays: '几天到期',
    userresponsible: '回应用户',
    manager: '管理员',
    lastcompleted: '创建日期',
  },
  fixedAssetItem: {
    assetid: '序号',
    serialno: '序列号',
    barcode: '条形码',
    assetlocation: '资产地点',
    cost: '',
    accumdepn: '',
    datepurchased: '',
    disposalproceeds: '',
    assetcategoryid: '资产种类',
    description: '资产摘要',
    longdescription: '详细描述',
    depntype: '折旧类型',
    depnrate: '折旧率',
    disposaldate: '',
  },
  fixedAssets: {
    assetid: '序号',
    serialno: '序列号',
    barcode: '条形码',
    assetlocation: '资产地点',
    cost: '',
    accumdepn: '',
    datepurchased: '采购日期',
    disposalproceeds: '',
    assetcategoryid: '资产种类',
    description: '资产摘要',
    longdescription: '详细描述',
    depntype: '折旧类型',
    depnrate: '折旧率',
    disposaldate: '上次执行折旧日期',
  },
}
