export function uploadImg(data) {
    return fetch({
        url: '/api/editor/upload',
        method: 'post',
        data,
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
}