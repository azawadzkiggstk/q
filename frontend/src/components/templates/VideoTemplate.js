import React from 'react';

export const VideoTemplate = (props) => {
  const { video } = props;
  const { videoSource, videoUrl, localFile } = video.acf;
  const localVideoUrl = localFile?.mediaItemUrl;

  return (
    <article id={`video-${video.databaseId}`} className="video">
      {videoSource === 'external' && (
        <iframe
          title={video.title}
          width="100%"
          height="100%"
          src={videoUrl}
          frameBorder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
        />
      )}
      {videoSource === 'local' && (
        <video className="video__player" src={localVideoUrl} controls>
          <track default kind="captions" />
          Sorry, your browser doesn't support embedded videos.
        </video>
      )}
    </article>
  );
};
