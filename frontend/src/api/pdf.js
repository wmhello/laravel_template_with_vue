import fetch from '@/utils/fetch'

export function test() {
    return fetch({
        url: '/api/test',
        method: 'post',
        responseType: 'blob'
    })
}

