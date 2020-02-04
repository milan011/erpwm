import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function infoList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/infoSelfList',
    method: 'get',
    params: {query, token, page:query.page}
  })
}

export function getInfo(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getInfo/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function infoStatistics(query, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoStatistics',
    method: 'post',
    params: {query, token},
  })
}

export function createInfo(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoSelf',
    method: 'post',
    data,
    params: {token}
  })
}

export function updateInfo(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoSelf/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deleteInfo(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/infoSelf/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}


