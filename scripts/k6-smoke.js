import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 5,
  duration: '1m',
};

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';

export default function () {
  const res = http.get(`${BASE_URL}`);
  check(res, {
    'status is 200/302': (r) => r.status === 200 || r.status === 302,
  });
  sleep(1);
}


