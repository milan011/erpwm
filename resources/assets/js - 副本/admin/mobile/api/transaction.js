import request from '@/utils/request'
import { ROAST_CONFIG } from '../config.js'

export function fetchList(query) {
  return request({
    url: '/transaction/list',
    method: 'get',
    params: query
  })
}
