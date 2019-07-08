import request from '@/utils/request'
import { ROAST_CONFIG } from '../config.js'

export function loginByUsername(username, password) {
  const data = {
    username,
    password
  }
  return request({
    url: '/login',
    // url: 'www.zhuorui.net/admin/user/log',
    method: 'post',
    data
  })
}

export function logout(token) {
  return request({
    // url: '/login/logout',
    url: ROAST_CONFIG.API_URL + '/userLogout',
    method: 'post',
    params: { token }
  })
}

export function getUserInfo(token) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getUserInfo',
    method: 'get',
    params: { token }
  })
}

