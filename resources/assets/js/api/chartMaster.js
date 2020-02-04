import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getChartMasterList(query) {
  return request({
    // url: '/chartmaster/list',
    url: ROAST_CONFIG.API_URL + '/chartMasterList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function getChartMasterGLB(data) {
  return request({
    // url: '/chartmaster/list',
    url: ROAST_CONFIG.API_URL + '/getChartMasterGLB/' + data.id,
    method: 'get',
    params: {token}
  })
}

export function setChartMasterGLB(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/setChartMasterGLB/',
    method: 'put',
    params: { token },
    data
  })
}

export function chartMasterAll() {
  return request({
    // url: '/chartMaster/list',
    url: ROAST_CONFIG.API_URL + '/chartMasterList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createChartMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/chartMaster',
    method: 'post',
    params: { token },
    data
  })
}


export function updateChartMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/chartMaster/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}

export function deleteChartMaster(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/chartMaster/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
