import fetch from '@/utils/fetch'

export function login(data) {
    return fetch({
        url: '/api/login',
        method: 'post',
        data: {
            email: data.username,
            password: data.password
        }
    })
}

export function loginWithThree(username, password, platformId, provider) {
    return fetch({
        url: '/api/loginWithThree',
        method: 'post',
        data: {
            email: username,
            password,
            platformId,
            provider
        }
    })
}

export function getInfo() {
    return fetch({
        url: '/api/user',
        method: 'get'
    })
}

export function logout() {
    return fetch({
        url: '/api/logout',
        method: 'post'
    })
}

export function loginToken() {
    return fetch({
        url: '/api/token/refresh',
        method: 'post'
    })
}

export function refreshTokenFn()
{
  return new Promise((resolve, reject) =>
  {
    axios(
      {
        url: `${process.env.VUE_APP_BASE_API}/api/refresh`,
        method: "get",
        params:
        {
          refresh_token: getRefreshToken()
        }
      })
      .then(response =>
      {
        // return response;
        resolve(response);
      })
      .catch(error =>
      {
        resolve(error.response);
      });
  });
}
