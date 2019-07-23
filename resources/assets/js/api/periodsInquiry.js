import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPeriodsInquiryList(query) {
  return request({
    // url: '/periodsInquiryList/list',
    url: ROAST_CONFIG.API_URL + '/periodsInquiryList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function periodsInquiryListAll() {
  return request({
    // url: '/periodsInquiryList/list',
    url: ROAST_CONFIG.API_URL + '/periodsInquiryList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPeriodsInquiry(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/periodsInquiryList',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePeriodsInquiry(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/periodsInquiryList/' + data.periodno,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePeriodsInquiry(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/periodsInquiryList/' + data.periodno,
    method: 'delete',
    params: {token},
    data
  }) 
}
