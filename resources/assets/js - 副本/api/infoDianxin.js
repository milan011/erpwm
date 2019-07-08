import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function infoDianxinList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/infoDianxinList',
    method: 'get',
    params: {query, token, page:query.page}
  })
}

export function getInfoDianxin(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getInfo/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createInfoDianxin(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoDianxin',
    method: 'post',
    data,
    params: {token}
  })
}

export function importInfoDianxin(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/importInfoDianxin',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateInfoDianxin(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoDianxin/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function dealInfoDianxin(token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoDianxin/' + 'dealWith',
    method: 'get',
    params: {token},
  })
}

export function deleteInfoDianxin(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoDianxin/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}


