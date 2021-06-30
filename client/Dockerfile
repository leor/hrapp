FROM node:16-alpine as builder
WORKDIR /var/www/client
COPY package.json /var/www/client/package.json
RUN npm install
COPY . /var/www/client
RUN npm run build
FROM nginx:1.21.0-alpine
COPY --from=builder /var/www/client/build /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
