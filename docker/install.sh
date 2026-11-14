#!/usr/bin/env bash
# Chạy trình cài đặt NukeViet qua HTTP với thông số trong .env.
# Dùng: ./docker/install.sh            (site chính)
# Không in mật khẩu ra màn hình.
set -euo pipefail
cd "$(dirname "$0")/.."
set -a; source .env; set +a

BASE="http://localhost:${HTTP_PORT}"
SITE_DIR=${SITE_DIR:-site}
JAR=.work/install-cookies.txt
mkdir -p .work; rm -f "$JAR"

[ -d "$SITE_DIR/install" ] || { echo "Không có $SITE_DIR/install: site đã cài hoặc mã nguồn thiếu." >&2; exit 1; }

B="$BASE/install/index.php"
# NukeViet từ chối POST không cùng nguồn gốc nên phải gửi kèm Referer và Origin.
c() { curl -s -b "$JAR" -c "$JAR" -e "$B" -H "Origin: $BASE" "$@" -o /dev/null -w '%{http_code} '; }
L="language=${NV_LANG}"

for i in $(seq 1 30); do curl -fsS "$BASE/install/" >/dev/null 2>&1 && break; sleep 2; done

c "$B?$L&step=1"
c "$B?$L&step=2"
c "$B?$L&step=3"
c "$B?$L&step=4"
c --data-urlencode "dbhost=db" --data-urlencode "dbport=" \
  --data-urlencode "dbname=${DB_NAME}" --data-urlencode "dbuname=${DB_USER}" \
  --data-urlencode "dbpass=${DB_PASS}" \
  -d "prefix=nv4&db_detete=1&lang_multi=0" "$B?$L&step=5"
c --data-urlencode "site_name=${NV_SITE_NAME}" \
  --data-urlencode "nv_login=${NV_ADMIN_USER}" \
  --data-urlencode "nv_email=${NV_ADMIN_EMAIL}" \
  --data-urlencode "nv_password=${NV_ADMIN_PASS}" \
  --data-urlencode "re_password=${NV_ADMIN_PASS}" \
  -d "question=q&answer_question=a&lang_multi=0&dev_mode=0&submit=1" "$B?$L&step=6"
c "$B?$L&step=7"
c "$B?$L&step=8"
echo

if [ -f "$SITE_DIR/config.php" ]; then
  rm -rf "$SITE_DIR/install"
  echo "Đã cài xong. Đã xoá $SITE_DIR/install. Tài khoản quản trị nằm trong .env."
else
  echo "Trình cài đặt chưa hoàn tất: không thấy $SITE_DIR/config.php." >&2
  exit 1
fi
