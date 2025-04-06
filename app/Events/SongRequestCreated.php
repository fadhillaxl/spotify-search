<?php

namespace App\Events;

use App\Models\SongRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SongRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $songRequest;

    public function __construct(SongRequest $songRequest)
    {
        $this->songRequest = $songRequest;
    }

    public function broadcastOn()
    {
        return new Channel('song-requests');
    }

    public function broadcastAs()
    {
        return 'request.created';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->songRequest->id,
            'song_name' => $this->songRequest->song_name,
            'artist' => $this->songRequest->artist,
            'name' => $this->songRequest->name,
            'status' => $this->songRequest->status,
            'created_at' => $this->songRequest->created_at->format('M d, Y h:i A'),
        ];
    }

    public function broadcastWhen()
    {
        return true;
    }
} 