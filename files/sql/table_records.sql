CREATE TABLE records (
    -- UUIDv7
    id            TEXT    PRIMARY KEY
                          NOT NULL
                          COLLATE NOCASE,
    category_id   INTEGER REFERENCES categories (id) ON UPDATE SET NULL,
    -- JSON
    body          TEXT    COLLATE NOCASE,
    -- example value 1766711695970
    created_at_ms INTEGER NOT NULL
);
