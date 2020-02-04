import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getSalesGLPostingList(query) {
  return request({
    // url: '/salesGLPosting/list',
    url: ROAST_CONFIG.API_URL + '/salesGLPostingList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function salesGLPostingAll() {
  return request({
    // url: '/salesGLPosting/list',
    url: ROAST_CONFIG.API_URL + '/salesGLPostingList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createSalesGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesGLPosting',
    method: 'post',
    params: { token },
    data
  })
}


export function updateSalesGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesGLPosting/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteSalesGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/salesGLPosting/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
