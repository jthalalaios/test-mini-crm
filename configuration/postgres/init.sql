-- init.sql

DO $$
BEGIN
   IF NOT EXISTS (SELECT FROM pg_database WHERE datname = 'test_mini') THEN
      CREATE DATABASE test_mini;
   END IF;
END$$;

DO $$
BEGIN
   IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'test_mini_user') THEN
      CREATE USER test_mini_user WITH PASSWORD '123TestAppMiniUser#321!';
   ELSE
      ALTER USER test_mini_user WITH PASSWORD '123TestAppMiniUser#321!';
   END IF;
END$$;

GRANT ALL PRIVILEGES ON DATABASE test_mini TO test_mini_user;
\c test_mini;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO test_mini_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO test_mini_user;
GRANT USAGE ON SCHEMA public TO test_mini_user;