-- #!mysql

-- #{ table
	-- #{ users
		CREATE TABLE IF NOT EXISTS RankSystemUsers
		(
			name           VARCHAR(32) PRIMARY KEY NOT NULL,
			ranks          TEXT        DEFAULT NULL,
			permissions    TEXT        DEFAULT NULL
		);
	-- #}
-- #}

-- #{ data
	-- #{ users
		-- #{ add
			-- # :name string
			-- # :ranks string ""
			-- # :permissions string ""
			INSERT OR IGNORE INTO
			RankSystemUsers(name, ranks, permissions)
			VALUES (:name, :ranks, :permissions);
		-- #}
		-- #{ get
			-- # :name string
			SELECT * FROM RankSystemUsers WHERE name = :name;
		-- #}
		-- #{ set
			-- # :name string
			-- # :ranks string ""
			-- # :permissions string ""
			INSERT OR REPLACE INTO
			RankSystemUsers(name, ranks, permissions)
			VALUES (:name, :ranks, :permissions);
		-- #}
		-- #{ getAll
			SELECT * FROM RankSystemUsers;
		-- #}
		-- #{ setRanks
			-- # :name string
			-- # :ranks string
			INSERT INTO RankSystemUsers(name, ranks)
				VALUES(:name, :ranks)
				ON DUPLICATE KEY UPDATE
					ranks = :ranks;
		-- #}
		-- #{ setPermissions
			-- # :name string
			-- # :permissions string
			INSERT INTO RankSystemUsers(name, permissions)
				VALUES(:name, :permissions)
				ON DUPLICATE KEY UPDATE
					permissions = :permissions;
		-- #}
		-- #{ delete
			-- # :name string
			DELETE FROM RankSystemUsers WHERE name = :name;
		-- #}
	-- #}
-- #}