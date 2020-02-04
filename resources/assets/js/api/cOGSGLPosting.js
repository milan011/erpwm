import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getCOGSGLPostingList(query) {
  return request({
    // url: '/cOGSGLPosting/list',
    url: ROAST_CONFIG.API_URL + '/cOGSGLPostingList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function cOGSGLPostingAll() {
  return request({
    // url: '/cOGSGLPosting/list',
    url: ROAST_CONFIG.API_URL + '/cOGSGLPostingList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createCOGSGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/cOGSGLPosting',
    method: 'post',
    params: { token },
    data
  })
}


export function updateCOGSGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/cOGSGLPosting/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteCOGSGLPosting(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/cOGSGLPosting/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
