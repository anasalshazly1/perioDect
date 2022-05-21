import glob, cv2,os
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3' 

import matplotlib.image as mpimg
import numpy as np
from tensorflow import keras
from keras.layers import Embedding
from keras.applications.vgg19 import VGG19
from tensorflow.keras.models import Sequential, Model
from tensorflow.keras.layers import Input, Conv2D, Activation, MaxPool2D, Dense, Dropout, Flatten, BatchNormalization, GlobalAveragePooling2D, Embedding,GlobalMaxPooling2D
from flask import request
from silence_tensorflow import silence_tensorflow
silence_tensorflow()
from flask import Flask, request
import sys


if __name__ == '__main__':

    image_size = 128, 128
    base_model = VGG19(include_top=False)
    x = base_model.output
    x = GlobalMaxPooling2D()(x)
    x = Dense(1024, activation='relu')(x)
    predictions = Dense(2, activation='softmax')(x)
    model = Model(inputs=base_model.input, outputs=predictions)
    for layer in base_model.layers[0:20]:
        layer.trainable = False
    for layer in base_model.layers[20:]:
        layer.trainable = True

    weights = []
    weights = glob.glob("../api/model_weights/*")
    weights.sort(reverse=True)


    def load_existing_model_weights():
        from pathlib import Path
        user_home = str(Path.home())
        model.load_weights(weights[0])
        return model


    model = load_existing_model_weights()


    def load_image(file):
        try:
            img = cv2.imread(file)
            img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            img = cv2.resize(img, image_size)
            return img
        except:
            print("None")


    def classify_teeth():
        if (model):
            file_loc = os.path.join("../images", sys.argv[1])
            test_image = load_image(file_loc)
            img = np.asarray(test_image)
            img = img / 255.0
            img = img.reshape(-1, 128, 128, 3)
            prediction = model.predict(img)

            if (np.argmax(model.predict(img), axis=-1) == [1]):
                return "Periodontal"
            elif (np.argmax(model.predict(img), axis=-1) == [0]):
                return "Non-Periodontal"
            


    cls = classify_teeth()

    print(cls)
