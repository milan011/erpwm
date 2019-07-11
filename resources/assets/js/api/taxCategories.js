import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getTaxCategoriesList(query) {
  return request({
    // url: '/taxCategories/list',
    url: ROAST_CONFIG.API_URL + '/taxCategoriesList',
    method: 'get',
    params: { query, token, page: query.page }
  })
}

export function taxCategoriesAll() {
  return request({
    // url: '/taxCategories/list',
    url: ROAST_CONFIG.API_URL + '/taxCategoriesList',
    method: 'get',
    params: { token, withOutPage: true }
  })
}

export function createTaxCategories(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxCategories',
    method: 'post',
    params: { token },
    data
  })
}


export function updateTaxCategories(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxCategories/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deleteTaxCategories(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/taxCategories/' + data.id,
    method: 'delete',
    params: { token },
    data
  })
}