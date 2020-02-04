import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getTagsList(query) {
  return request({
    // url: '/tags/list',
    url: ROAST_CONFIG.API_URL + '/tagsList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function tagsAll() {
  return request({
    // url: '/tags/list',
    url: ROAST_CONFIG.API_URL + '/tagsList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createTags(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/tags',
    method: 'post',
    params: { token },
    data
  })
}

export function updateTags(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/tags/' + data.tagref,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteTags(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/tags/' + data.tagref,
    method: 'delete',
    params: {token},
    data
  }) 
}
