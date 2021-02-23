import ws from 'k6/ws';
import { check } from 'k6';


export let options = {
  vus: 10,
  duration: '25s',
};

export default () => {
  const url = 'ws://localhost/v1/realtime';
  const res = ws.connect(url, (socket) => {
    socket.close();
  });

  check(res, { 'status is 101': r => r && r.status == 101 });
}