import request from '@/utils/request'
import { ROAST_CONFIG } from '../config.js'

export function userSearch(name) {
  return request({
    url: '/search/user',
    method: 'get',
    params: { name }
  })
}
