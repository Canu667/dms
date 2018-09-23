<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Contract\SupportedMimeTypes;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180728224947 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, file_name VARCHAR(120) NOT NULL, INDEX IDX_A2B07288C54C8C93 (type_id), INDEX idx_document_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_types (id INT AUTO_INCREMENT NOT NULL, mime_type VARCHAR(100) NOT NULL, INDEX idx_document_type_name (mime_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288C54C8C93 FOREIGN KEY (type_id) REFERENCES document_types (id)');

        $supportedMimeTypes = new SupportedMimeTypes();

        foreach ($supportedMimeTypes->getMimeTypes() as $mimeType) {
            $this->addSql(
                sprintf(
                    "INSERT INTO `document_types` (`mime_type`) VALUES ('%s')",
                    $mimeType
                )
            );
        }
    }

    public function down(Schema $schema): void
    {
        $supportedMimeTypes = new SupportedMimeTypes();

        foreach ($supportedMimeTypes->getMimeTypes() as $mimeType) {
            $this->addSql(
                sprintf(
                    "DELETE FROM `document_types` WHERE `mime_type` = '%s';",
                    $mimeType
                )
            );
        }

        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288C54C8C93');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE document_types');
    }
}
