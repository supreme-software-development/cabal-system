IF OBJECT_ID('[dbo].[cabal_tool_registerAccount_web]', 'P') IS NOT NULL
    DROP PROCEDURE [dbo].[cabal_tool_registerAccount_web];
GO

SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[cabal_tool_registerAccount_web] (
    @ID VARCHAR(50),
    @email VARCHAR(100),
    @password VARCHAR(50)
)
AS
BEGIN
    SET NOCOUNT ON;
    
    IF LEN(@ID) > 50 OR LEN(@ID) < 3
    BEGIN
        RAISERROR('Username must be between 3 and 50 characters', 16, 1, 50003);
        RETURN;
    END

    IF LEN(@email) > 100
    BEGIN
        RAISERROR('Email is too long', 16, 1, 50004);
        RETURN;
    END

    IF LEN(@password) < 8
    BEGIN
        RAISERROR('Password must be at least 8 characters', 16, 1, 50005);
        RETURN;
    END

    IF @password LIKE '%[^A-Za-z0-9]%'
    BEGIN
        RAISERROR('Password can only contain letters and numbers', 16, 1, 50006);
        RETURN;
    END
    
    IF EXISTS (SELECT 1 FROM cabal_auth_table WHERE ID = @ID)
    BEGIN
        RAISERROR('Username already exists', 16, 1, 50001);
        RETURN;
    END

    -- Check if email exists
    IF EXISTS (SELECT 1 FROM cabal_auth_table WHERE email = @email)
    BEGIN
        RAISERROR('Email already exists', 16, 1, 50002);
        RETURN;
    END

    BEGIN TRY
        INSERT INTO cabal_auth_table (ID, email, Password, createDate, Login)
        VALUES (@ID, @email, PWDENCRYPT(@password), GETDATE(), 0);
    END TRY
    BEGIN CATCH
        -- DECLARE @ErrorMessage NVARCHAR(4000);
        -- DECLARE @ErrorSeverity INT;
        -- DECLARE @ErrorState INT;

        -- SELECT 
            -- @ErrorMessage = ERROR_MESSAGE();
            -- @ErrorSeverity = ERROR_SEVERITY(),
            -- @ErrorState = ERROR_STATE();

        -- , @ErrorSeverity, @ErrorState, 
        RAISERROR ('One or more fields are invalid', 16, 1, 50000);
        RETURN;
    END CATCH
END
GO