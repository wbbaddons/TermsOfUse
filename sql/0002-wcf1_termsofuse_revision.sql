CREATE TABLE wcf1_termsofuse_revision ( revisionID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY
                                      , enabledAt  INT(10) DEFAULT NULL
                                      );

CREATE TABLE wcf1_termsofuse_revision_content ( contentID  INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY
                                              , revisionID INT(10) NOT NULL
                                              , languageID INT(10) NOT NULL
                                              , content MEDIUMTEXT
                                              , hasEmbeddedObjects TINYINT(1) NOT NULL DEFAULT 0
                                              , UNIQUE KEY(revisionID, languageID)
                                              );

ALTER TABLE wcf1_termsofuse_revision_content ADD FOREIGN KEY (revisionID) REFERENCES wcf1_termsofuse_revision (revisionID) ON DELETE CASCADE;
ALTER TABLE wcf1_termsofuse_revision_content ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE CASCADE;
ALTER TABLE wcf1_user ADD FOREIGN KEY (termsOfUseRevision) REFERENCES wcf1_termsofuse_revision (revisionID) ON DELETE SET NULL;
