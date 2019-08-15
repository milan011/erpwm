import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getMaintenanceTaskList(query) {
  return request({
    // url: '/maintenanceTask/list',
    url: ROAST_CONFIG.API_URL + '/maintenanceTaskList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function maintenanceTaskAll() {
  return request({
    // url: '/maintenanceTask/list',
    url: ROAST_CONFIG.API_URL + '/maintenanceTaskList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createMaintenanceTask(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/maintenanceTask',
    method: 'post',
    params: { token },
    data
  })
}


export function updateMaintenanceTask(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/maintenanceTask/' + data.taskid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteMaintenanceTask(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/maintenanceTask/' + data.taskid,
    method: 'delete',
    params: {token},
    data
  }) 
}
