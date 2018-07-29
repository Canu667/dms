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
    }
}
