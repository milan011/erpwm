import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getDepartmentList(query) {
  return request({
    // url: '/department/list',
    url: ROAST_CONFIG.API_URL + '/departmentList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function departmentAll() {
  return request({
    // url: '/department/list',
    url: ROAST_CONFIG.API_URL + '/departmentList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createDepartment(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/department',
    method: 'post',
    params: { token },
    data
  })
}


export function updateDepartment(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/department/' + data.departmentid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteDepartment(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/department/' + data.departmentid,
    method: 'delete',
    params: {token},
    data
  }) 
}
