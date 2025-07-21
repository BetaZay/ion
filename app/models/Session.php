<?php


namespace app\models;

use core\models\Model;

class Session extends Model
{
    protected static string $table = 'sessions';
    protected static string $primaryKey = 'id';

    public function getId(): string
    {
        return $this->get('id');
    }

    public function getUserId(): ?int
    {
        return $this->get('user_id');
    }

    public function getIpAddress(): ?string
    {
        return $this->get('ip_address');
    }

    public function getUserAgent(): ?string
    {
        return $this->get('user_agent');
    }

    public function getPayload(): string
    {
        return $this->get('payload');
    }

    public function getLastActivity(): int
    {
        return $this->get('last_activity');
    }

    public function touch(): void
    {
        $this->update([
            'last_activity' => time(),
        ]);
    }

    public static function findOrCreate(string $id, array $defaults = []): static
    {
        $existing = static::find($id);
        if ($existing) {
            return $existing;
        }

        $new = new static(array_merge(['id' => $id], $defaults));
        $new->save();
        return $new;
    }
}
