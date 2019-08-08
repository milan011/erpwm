import request from '@/utils/request'
import { getToken } from '@/utils/auth'
import { ROAST_CONFIG } from '../config.js'

let token = getToken()

export function getPcExpensesList(query) {
  return request({
    // url: '/pcExpenses/list',
    url: ROAST_CONFIG.API_URL + '/pcExpensesList',
    method: 'get',
    params: {token, query, page:query.page}
  })
}

export function pcExpensesAll() {
  return request({
    // url: '/pcExpenses/list',
    url: ROAST_CONFIG.API_URL + '/pcExpensesList',
    method: 'get',
    params: {token, withOutPage:true}
  })
}

export function createPcExpenses(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcExpenses',
    method: 'post',
    params: { token },
    data
  })
}


export function updatePcExpenses(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcExpenses/' + data.id,
    method: 'put',
    params: { token },
    data
  })
}
export function deletePcExpenses(data) {
  return request({
    url: ROAST_CONFIG.API_URL + '/pcExpenses/' + data.id,
    method: 'delete',
    params: {token},
    data
  }) 
}
