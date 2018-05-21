CREATE TABLE wcf1_termsofuse_revision_to_user ( revisionID INT(10) NOT NULL
                                              , userID  INT(10) NOT NULL
                                              , acceptedAt  INT(10) DEFAULT NULL
                                              , PRIMARY KEY (revisionID, userID)
                                              );

ALTER TABLE wcf1_termsofuse_revision_to_user ADD FOREIGN KEY (revisionID) REFERENCES wcf1_termsofuse_revision (revisionID) ON DELETE CASCADE;
ALTER TABLE wcf1_termsofuse_revision_to_user ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

INSERT INTO wcf1_termsofuse_revision_to_user (revisionID, userID, acceptedAt)
SELECT  termsOfUseRevision, userID, NULL
FROM    wcf1_user
WHERE   termsOfUseRevision IS NOT NULL;

ALTER TABLE wcf1_user DROP FOREIGN KEY termsOfUseRevision;
ALTER TABLE wcf1_user DROP COLUMN termsOfUseRevision;
