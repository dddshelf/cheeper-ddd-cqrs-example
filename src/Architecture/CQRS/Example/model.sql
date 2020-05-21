-- snippet model-example
-- Definition of a UI view of a single post with its comments
CREATE TABLE single_post_with_comments (
    id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    post_title VARCHAR(100) NOT NULL,
    post_content TEXT NOT NULL,
    post_created_at DATETIME NOT NULL,
    comment_content TEXT NOT NULL
);

-- Set up some data
INSERT INTO single_post_with_comments VALUES
    (1, 1, "Layered architecture", "Lorem ipsum ...", NOW(), "Lorem ipsum ..."),
    (2, 1, "Layered architecture", "Lorem ipsum ...", NOW(), "Lorem ipsum ..."),
    (3, 2, "Hexagonal architecture", "Lorem ipsum ...", NOW(), "Lorem ipsum ..."),
    (4, 2, "Hexagonal architecture", "Lorem ipsum ...", NOW(), "Lorem ipsum ..."),
    (5, 3, "CQRS", "Lorem ipsum ...", NOW(), "Lorem ipsum ..."),
    (6, 3, "CQRS", "Lorem ipsum ...", NOW(), "Lorem ipsum ...");

-- Query it
SELECT * FROM single_post_with_comments WHERE post_id = 1;
-- end-snippet

-- snippet query-example
SELECT * FROM posts_grouped_by_month_and_year ORDER BY month DESC, year ASC;
SELECT * FROM posts_by_tags WHERE tag = "ddd";
SELECT * FROM posts_by_author WHERE author_id = 1;
-- end-snippet
