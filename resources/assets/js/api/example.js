import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getExampleList(query) {
  return request({
    // url: '/example/list',
    url: ROAST_CONFIG.API_URL + '/exampleList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function exampleAll() {
  return request({
    // url: '/example/list',
    url: ROAST_CONFIG.API_URL + '/exampleList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createExample(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/example',
    method: 'post',
    params: { token },
    data
  })
}


export function updateExample(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/example/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteExample(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/example/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
