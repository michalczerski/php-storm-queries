FROM postgres:16-alpine3.18 as postgres
#ENV POSTGRES_DB postgres
ENV POSTGRES_USER postgres
ENV POSTGRES_PASSWORD postgres
#COPY postgres.sql /docker-entrypoint-initdb.d

