import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getUnitsOfMeasureList(query) {
  return request({
    // url: '/unitsOfMeasure/list',
    url: ROAST_CONFIG.API_URL + '/unitsOfMeasureList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function unitsOfMeasureAll() {
  return request({
    // url: '/unitsOfMeasure/list',
    url: ROAST_CONFIG.API_URL + '/unitsOfMeasureList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createUnitsOfMeasure(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/unitsOfMeasure',
    method: 'post',
    params: { token },
    data
  })
}


export function updateUnitsOfMeasure(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/unitsOfMeasure/' + data.unitid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteUnitsOfMeasure(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/unitsOfMeasure/' + data.unitid,
    method: 'delete',
    params: {token},
    data
  }) 
}
