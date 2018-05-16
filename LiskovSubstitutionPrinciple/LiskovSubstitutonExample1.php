<?php

class VideoPlayer
{
    public function play($file)
    {
        // play the video
    }
}

class AviVideoPlayer extends VideoPlayer
{
    public function play($file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'avi') {
            /*
                Violates LSP because:
                  - preconditions for the subclass can't be greater
                  - we can no longer substitute this behaviour anywhere else because the output could be different
             */
            throw new Exception;
        }
    }
}