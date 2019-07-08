import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function noticeList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/noticeList',
    method: 'get',
    query,
    params: {token, page:query.page}
  })
}

export function noticeAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/noticeAll',
    method: 'get',
    params: {token}
  })
}

export function getNotice(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getNotice/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createNotice(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/notice',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateNotice(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/notice/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteNotice(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/notice/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

