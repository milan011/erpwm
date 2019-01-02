import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function packageList(query, token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/packageList',
    method: 'get',
    params: {token, page:query.page}
  })
}

export function packageAll(token = getToken()) {
  return request({
    // url: '/user/list',
    url: ROAST_CONFIG.API_URL + '/packageAll',
    method: 'get',
    params: {token}
  })
}

export function getPackage(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/getPackage/' + data.id,
    method: 'get',
    params: {token},
  })
}

export function createPackage(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/package',
    method: 'post',
    data,
    params: {token}
  })
}

export function updatePackage(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/package/' + data.id,
    method: 'put',
    params: {token},
    data
  })
}

export function deletePackage(data, token = getToken()) {
  return request({
    url: ROAST_CONFIG.API_URL + '/package/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}

