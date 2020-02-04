import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getMRPCalendarList(query) {
  return request({
    // url: '/mRPCalendar/list',
    url: ROAST_CONFIG.API_URL + '/mRPCalendarList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function mRPCalendarAll() {
  return request({
    // url: '/mRPCalendar/list',
    url: ROAST_CONFIG.API_URL + '/mRPCalendarList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createMRPCalendar(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPCalendar',
    method: 'post',
    params: { token },
    data
  })
}


export function updateMRPCalendar(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPCalendar/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteMRPCalendar(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/mRPCalendar/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
