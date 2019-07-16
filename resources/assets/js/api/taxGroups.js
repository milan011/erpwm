import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getTaxGroupsList(query) {
  return request({
    // url: '/taxGroups/list',
    url: ROAST_CONFIG.API_URL + '/taxGroupsList',
    method: 'get',
    params: { query, token, page: query.page }
  })
}

export function taxGroupsAll() {
  return request({
    // url: '/taxGroups/list',
    url: ROAST_CONFIG.API_URL + '/taxGroupsList',
    method: 'get',
    params: { token, withOutPage: true }
  })
}

export function createTaxGroups(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxGroups',
    method: 'post',
    params: { token },
    data
  })
}


export function updateTaxGroups(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxGroups/' + data.taxgroupid,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteTaxGroups(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxGroups/' + data.taxgroupid,
    method: 'delete',
    params: { token },
    data
  })
}